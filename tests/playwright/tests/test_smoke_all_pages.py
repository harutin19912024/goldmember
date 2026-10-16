"""
Smoke test: every reachable page answers without a server error.

Fast (HTTP requests through the logged-in browser context, no rendering), so it
can run on every change. Customer site: the sitemap plus account pages, as a
logged-in customer. Admin: every sidebar link plus the first Update/View link on
each list page, as an admin. Fails on HTTP >= 500 or PHP/Yii error text.

Never follows links that change data (delete, status toggles, ...).
"""
import re
from urllib.parse import urljoin, urlparse
import pytest
from playwright.sync_api import Page

FRONTEND = "http://localhost:8080"
BACKEND = "http://localhost:8090"
ERROR_TEXT = re.compile(
    r"PHP (Fatal|Warning|Notice|Deprecated)|Stack trace|Undefined (variable|index|array key)|"
    r"Unknown Property|SQLSTATE|Database Exception|Invalid Configuration|"
    r"Call to (a member function|undefined)|Trying to access array offset")
UNSAFE = re.compile(
    r"logout|delete|remove|close-auction|fetch-latest|changestatus|change-items-status|"
    r"changeitemsstatus|allow-to-add|default-image|addinslider|add-in-slider|update-ordering|"
    r"save-prices|create-metal-prices|fix-new|update-image-type|place-bid|change-language|"
    r"^mailto:|^tel:|^javascript:|#", re.I)

CUSTOMER_PAGES = ["/am/user/profile", "/am/user/edit-password", "/am/product/favorite",
                  "/am/login", "/am/register", "/am/forgot-password", "/en/auction/13"]


def _login(page: Page, base: str, path: str, user: str, password: str, submit: str):
    page.goto(base + path)
    page.fill('input[name="LoginForm[username]"]', user)
    page.fill('input[name="LoginForm[password]"]', password)
    with page.expect_navigation():
        page.click(submit)
    assert "login" not in page.url, f"login failed for {user}"


def _check(page: Page, urls):
    failures = []
    for url in sorted(set(urls)):
        resp = page.request.get(url, max_redirects=5)
        body = resp.text() if "html" in resp.headers.get("content-type", "") else ""
        m = ERROR_TEXT.search(body)
        if resp.status >= 500 or m:
            failures.append(f"{resp.status} {url} {m.group(0) if m else ''}")
    assert not failures, "Broken pages:\n" + "\n".join(failures)
    return len(set(urls))


def _same_site_links(page: Page, base: str):
    hrefs = page.eval_on_selector_all("a[href]", "els => els.map(e => e.getAttribute('href'))")
    out = []
    for h in hrefs:
        if not h or UNSAFE.search(h):
            continue
        u = urljoin(base + "/", h)
        if urlparse(u).netloc == urlparse(base).netloc:
            out.append(u.split("#")[0])
    return out


def test_customer_site_pages(page: Page):
    _login(page, FRONTEND, "/am/login", "e2etester", "test123", "button.login-submit-btn")
    sitemap = page.request.get(FRONTEND + "/sitemap.xml").text()
    urls = re.findall(r"<loc>([^<]+)</loc>", sitemap) + [FRONTEND + p for p in CUSTOMER_PAGES]
    assert len(urls) > 10, "sitemap.xml looks empty"
    _check(page, urls)


def test_admin_pages(page: Page):
    _login(page, BACKEND, "/am/site/login", "harut", "admin123", "form button[type=submit]")
    page.goto(BACKEND + "/am")
    sections = [u for u in _same_site_links(page, BACKEND) if "/site/" not in u]
    urls = set(sections)
    for section in sorted(sections):
        if not section.endswith(("/index", "/gallery", "/video")):
            continue
        page.goto(section)
        rows = [u for u in _same_site_links(page, BACKEND) if re.search(r"/(update|view)\?id=\d+", u)]
        urls.update(rows[:2])
    assert len(urls) > 30, f"only {len(urls)} admin URLs discovered"
    _check(page, urls)
