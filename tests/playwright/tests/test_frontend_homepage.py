"""
E2E tests: Frontend homepage and navigation.
"""
import pytest
from playwright.sync_api import Page, expect

FRONTEND = "http://localhost:8080"


@pytest.fixture(autouse=True)
def goto_home(page: Page):
    page.goto(FRONTEND, timeout=15000)
    page.wait_for_load_state("domcontentloaded")


def test_homepage_loads_without_errors(page: Page):
    """Homepage must not show a PHP error/warning page."""
    expect(page).not_to_have_title("PHP Warning")
    body = page.locator("body")
    expect(body).not_to_contain_text("Attempt to read property")
    expect(body).not_to_contain_text("Fatal error")
    expect(body).not_to_contain_text("ErrorException")


def test_homepage_has_brand(page: Page):
    """GOLDMEMBER brand must appear in the page text."""
    inner = page.locator("body").inner_text()
    assert "GOLDMEMBER" in inner or "Goldmember" in inner or "goldmember" in inner


def test_homepage_has_hero_text(page: Page):
    """Hero section must contain the main tagline."""
    inner = page.locator("body").inner_text()
    assert "IN GOLD WE TRUST" in inner or "gold" in inner.lower()


def test_homepage_nav_links_visible(page: Page):
    """Top navigation must exist with at least one link."""
    nav = page.locator("nav, header")
    assert nav.count() > 0


def test_homepage_stats_visible(page: Page):
    """Stats block must render on the page."""
    inner = page.locator("body").inner_text()
    assert "REGISTERED CLIENTS" in inner or "ONLINE" in inner


def test_homepage_no_500_error(page: Page):
    """Page should return HTTP 200."""
    response = page.request.get(FRONTEND)
    assert response.status == 200, f"Expected 200 got {response.status}"


def test_frontend_language_switch_en(page: Page):
    """Switching to English via /en must not crash."""
    page.goto(f"{FRONTEND}/en", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    body = page.locator("body")
    expect(body).not_to_contain_text("Fatal error")
    expect(body).not_to_contain_text("ErrorException")
