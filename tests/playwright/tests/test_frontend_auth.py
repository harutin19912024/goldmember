"""
E2E tests: Frontend user authentication (login, register pages).
"""
import pytest
from playwright.sync_api import Page, expect

FRONTEND = "http://localhost:8080"


def test_login_page_loads(page: Page):
    """Frontend login page must render without errors."""
    page.goto(f"{FRONTEND}/login", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")


def test_register_page_loads(page: Page):
    """Frontend registration page must render without errors."""
    page.goto(f"{FRONTEND}/register", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")


def test_forgot_password_page_loads(page: Page):
    """Forgot password page must render without errors."""
    page.goto(f"{FRONTEND}/forgot-password", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).not_to_contain_text("Fatal error")
