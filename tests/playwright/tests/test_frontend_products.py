"""
E2E tests: Product listing and detail pages.
"""
import pytest
from playwright.sync_api import Page, expect

FRONTEND = "http://localhost:8080"


def test_product_listing_page_loads(page: Page):
    """Product search/listing page must load without PHP error."""
    page.goto(f"{FRONTEND}/search", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    body = page.locator("body")
    expect(body).not_to_contain_text("Fatal error")
    expect(body).not_to_contain_text("PHP Warning")
    expect(body).not_to_contain_text("Undefined variable")


def test_product_listing_content_present(page: Page):
    """Product listing page must show content (not a blank or error screen)."""
    page.goto(f"{FRONTEND}/search", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    # Page must have a body with meaningful content
    assert len(page.locator("body").inner_text().strip()) > 50


def test_auction_page_loads(page: Page):
    """Auction page must load without PHP errors."""
    page.goto(f"{FRONTEND}/auction", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    body = page.locator("body")
    expect(body).not_to_contain_text("Fatal error")


def test_best_offer_page_loads(page: Page):
    """Best Offer page must load without PHP errors."""
    page.goto(f"{FRONTEND}/best-offer", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    body = page.locator("body")
    expect(body).not_to_contain_text("Fatal error")
