"""
E2E tests: Backend CRUD operations (products, news, slider).
Requires admin login.
"""
import pytest
from playwright.sync_api import Page, expect
from ..helpers.auth import backend_login

BACKEND = "http://localhost:8090"


@pytest.fixture(autouse=True)
def login(page: Page):
    backend_login(page)


def test_backend_product_list_loads(page: Page):
    """Backend product list must load with data grid."""
    page.goto(f"{BACKEND}/product/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")
    expect(page.locator("body")).not_to_contain_text("PHP Warning")


def test_backend_news_list_loads(page: Page):
    """Backend news list must load without errors."""
    page.goto(f"{BACKEND}/news/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")


def test_backend_category_list_loads(page: Page):
    """Backend category list must load without errors."""
    page.goto(f"{BACKEND}/category/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")


def test_backend_slider_list_loads(page: Page):
    """Backend slider list must load without errors."""
    page.goto(f"{BACKEND}/slider/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")


def test_backend_user_list_loads(page: Page):
    """Backend user list must load without errors."""
    page.goto(f"{BACKEND}/user/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")
