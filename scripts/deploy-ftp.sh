#!/usr/bin/env bash
# Upload the files changed between two commits to production over FTP, and delete the files that
# were removed. Dry run by default: prints the lftp script and exits.
#
#   scripts/deploy-ftp.sh <base-commit> [head-commit]            # dry run
#   FTP_PASS=... scripts/deploy-ftp.sh <base-commit> [head] --go  # really upload
#
# <base-commit> is what production currently runs. The remote FTP root mirrors this repo
# (frontend/, backend/, common/ ...), so every path is uploaded to the same relative dir.
# Dev-only paths and per-server files (.env, db-local.php, .htaccess, root index.php, gitignored
# configs) are never uploaded or deleted. See docs/PROGRESS-2026-10-16.md for the full checklist.
set -euo pipefail

FTP_HOST=${FTP_HOST:-31.22.4.234}
FTP_USER=${FTP_USER:-goldmember@goldmember.am}

base=${1:?usage: $0 <base-commit> [head-commit] [--go]}
head=HEAD; go=0
for arg in "${@:2}"; do
    if [ "$arg" = --go ]; then go=1; else head=$arg; fi
done

cd "$(git rev-parse --show-toplevel)"

# Never deployed: dev tooling, docs, SQL (run by hand in phpMyAdmin) and server-specific files.
skip='^(tests/|docker/|docs/|database/|scripts/|\.github/|\.claude/)|\.md$|^\.env|^\.gitignore$|(^|/)\.htaccess$|^index\.php$|^common/config/db-local\.php$|^docker-compose\.yml$|^Dockerfile$'

script=$(mktemp)
trap 'rm -f "$script"' EXIT
{
    echo "set ssl:verify-certificate no"
    echo "set net:timeout 30"
    echo "set net:max-retries 2"
    echo "set xfer:clobber yes"
    echo "set ftp:ssl-allow no"
} > "$script"

uploads=(); deletes=()
while IFS=$'\t' read -r status path; do
    [[ $path =~ $skip ]] && continue
    case $status in
        D) deletes+=("$path") ;;
        *) uploads+=("$path") ;;
    esac
done < <(git diff --no-renames --name-status "$base" "$head")

# Files must come from $head, not from whatever is in the working tree.
stage=$(mktemp -d)
trap 'rm -rf "$script" "$stage"' EXIT
for f in "${uploads[@]}"; do
    mkdir -p "$stage/$(dirname "$f")"
    git show "$head:$f" > "$stage/$f"
done

declare -A dirs=()
for f in "${uploads[@]}"; do dirs[$(dirname "$f")]=1; done
for d in $(printf '%s\n' "${!dirs[@]}" | sort); do
    [ "$d" = . ] || echo "mkdir -p -f $d" >> "$script"
done
for f in "${uploads[@]}"; do
    d=$(dirname "$f")
    echo "put -O $d $stage/$f" >> "$script"
done
for f in "${deletes[@]}"; do
    echo "rm -f $f" >> "$script"
done
# Schema/translation caches must be rebuilt after the SQL updates.
echo "glob -f rm -f frontend/runtime/cache/*/*" >> "$script"
echo "glob -f rm -f backend/runtime/cache/*/*" >> "$script"

echo "Base $base -> $head: ${#uploads[@]} uploads, ${#deletes[@]} deletions" >&2
if [ "$go" -ne 1 ]; then
    sed "s#$stage/##" "$script"
    echo "(dry run; add --go and set FTP_PASS to upload)" >&2
    exit 0
fi

: "${FTP_PASS:?set FTP_PASS}"
lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" < "$script"

# Verify: compare remote sizes with the local ones (one FTP session; `cls -l` prints ls-style lines).
listing=$(for f in "${uploads[@]}"; do echo "cls -l $f"; done |
    lftp -u "$FTP_USER","$FTP_PASS" "$FTP_HOST" -e "set ssl:verify-certificate no; set ftp:ssl-allow no" 2>&1)
bad=0
for f in "${uploads[@]}"; do
    want=$(stat -c%s "$stage/$f")
    got=$(awk -v p="$f" '$NF == p {print $4}' <<<"$listing")
    if [ "$want" != "$got" ]; then echo "SIZE MISMATCH $f local=$want remote=${got:-missing}"; bad=1; fi
done
[ "$bad" -eq 0 ] && echo "All ${#uploads[@]} uploads verified."
exit "$bad"
