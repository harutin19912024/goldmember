/**
 * Goldmember — Favorites toggle
 * Requires: jQuery (loaded by Yii2), window.GM_CSRF set in layout, window.GM_LOGGED_IN
 *
 * Usage: add  data-fav-id="{productId}"  to any heart button.
 * Add  data-fav-profile="1"  on buttons inside the profile favorites grid —
 * those cards will fade out when unfaved.
 */

(function () {
    'use strict';

    // Prevent double-fire for rapid clicks
    var _pending = {};

    // Wire up all heart buttons on DOM ready (single source of truth — no inline onclick needed)
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-fav-id]');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            _toggleFav(btn.dataset.favId, btn);
        });
    });

    function _toggleFav(productId, btn) {
        if (_pending[productId]) return;   // ignore double-click during request

        if (!window.GM_LOGGED_IN) {
            window.location.href = '/' + (window.GM_LANG || 'en') + '/login';
            return;
        }

        _pending[productId] = true;
        var icon = btn ? btn.querySelector('i') : null;

        // Optimistic UI — flip immediately so the user sees instant feedback
        var wasFilledBefore = icon && icon.classList.contains('bi-heart-fill');
        if (icon) {
            if (wasFilledBefore) {
                icon.classList.replace('bi-heart-fill', 'bi-heart');
                icon.style.color = '#aaa';
                btn.classList.remove('faved');
                btn.setAttribute('title', 'Add to favourites');
            } else {
                icon.classList.replace('bi-heart', 'bi-heart-fill');
                icon.style.color = '#13B2AD';
                btn.classList.add('faved');
                btn.setAttribute('title', 'Remove from favourites');
            }
        }

        $.ajax({
            url: '/' + (window.GM_LANG || 'en') + '/product/favorite',
            method: 'POST',
            data: { id: productId, _csrf: window.GM_CSRF },
            dataType: 'json',
            success: function (res) {
                if (!res.success) {
                    // Revert optimistic update on failure
                    _revertIcon(icon, btn, wasFilledBefore);
                    return;
                }

                var nowFaved = icon && icon.classList.contains('bi-heart-fill');

                // Sync all other buttons for the same product on the page
                document.querySelectorAll('[data-fav-id="' + productId + '"]').forEach(function (other) {
                    if (other === btn) return;
                    var otherIcon = other.querySelector('i');
                    if (!otherIcon) return;
                    if (nowFaved) {
                        otherIcon.classList.replace('bi-heart', 'bi-heart-fill');
                        otherIcon.style.color = '#13B2AD';
                        other.classList.add('faved');
                    } else {
                        otherIcon.classList.replace('bi-heart-fill', 'bi-heart');
                        otherIcon.style.color = '#aaa';
                        other.classList.remove('faved');
                    }
                });

                // Update header counter
                var counter = document.querySelector('.fav-count');
                if (counter) {
                    var n = parseInt(counter.textContent, 10) || 0;
                    counter.textContent = nowFaved ? n + 1 : Math.max(0, n - 1);
                }

                // If this button is inside the profile favourites grid and we just
                // UN-faved it → fade the card out and remove it from the DOM
                if (!nowFaved && btn.dataset.favProfile) {
                    var card = document.getElementById('fav-card-' + productId);
                    if (card) {
                        card.style.transition = 'opacity .3s, transform .3s';
                        card.style.opacity   = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(function () {
                            card.remove();
                            // If grid is now empty show the empty-state message
                            var grid = document.getElementById('fav-grid');
                            if (grid && !grid.querySelector('[id^="fav-card-"]')) {
                                grid.innerHTML = _emptyState();
                            }
                        }, 320);
                    }
                }
            },
            error: function () {
                _revertIcon(icon, btn, wasFilledBefore);
                console.warn('Favourite request failed.');
            },
            complete: function () {
                delete _pending[productId];
            }
        });
    }

    function _revertIcon(icon, btn, wasFilledBefore) {
        if (!icon) return;
        if (wasFilledBefore) {
            icon.classList.replace('bi-heart', 'bi-heart-fill');
            icon.style.color = '#13B2AD';
            btn && btn.classList.add('faved');
        } else {
            icon.classList.replace('bi-heart-fill', 'bi-heart');
            icon.style.color = '#aaa';
            btn && btn.classList.remove('faved');
        }
    }

    function _emptyState() {
        var lang = window.GM_LANG || 'en';
        return '<div class="col-12 text-center py-5">' +
            '<i class="bi bi-heart" style="font-size:3rem;color:#13B2AD;opacity:.4;"></i>' +
            '<p class="text-muted mt-3 mb-1">No favourites yet.</p>' +
            '<p class="text-muted small">Tap the heart icon on any product to save it here.</p>' +
            '<a href="/' + lang + '/search" class="btn btn-sm mt-2" ' +
            'style="background:#13B2AD;color:#fff;border-radius:20px;padding:6px 20px;">Browse Products</a>' +
            '</div>';
    }
})();
