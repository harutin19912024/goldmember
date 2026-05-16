"""
E2E tests: Backend admin authentication.
"""
import pytest
from playwright.sync_api import Page, expect

BACKEND = "http://localhost:8090"


def test_backend_login_page_loads(page: Page):
    """Backend login page must render the login form."""
    page.goto(BACKEND, timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator('input[name="LoginForm[username]"]')).to_be_visible()
    expect(page.locator('input[name="LoginForm[password]"]')).to_be_visible()


def test_backend_login_wrong_credentials(page: Page):
    """Login with wrong credentials must not redirect to dashboard."""
    page.goto(BACKEND, timeout=15000)
    page.fill('input[name="LoginForm[username]"]', "wronguser")
    page.fill('input[name="LoginForm[password]"]', "wrongpass")
    page.click('button[type="submit"]')
    page.wait_for_load_state("domcontentloaded")
    # Should stay on login page or show error
    expect(page.locator('input[name="LoginForm[username]"]')).to_be_visible()


def test_backend_login_admin_success(page: Page):
    """Admin can log in with valid credentials and reach the dashboard."""
    page.goto(BACKEND, timeout=15000)
    page.fill('input[name="LoginForm[username]"]', "harut")
    page.fill('input[name="LoginForm[password]"]', "admin123")
    page.click('button[type="submit"]')
    page.wait_for_load_state("domcontentloaded")
    # Should be redirected away from login
    expect(page.locator('input[name="LoginForm[username]"]')).not_to_be_visible()
    # Dashboard or settings should be visible
    expect(page.locator("body")).to_contain_text("GoldMember")


def test_backend_unauthenticated_redirect(page: Page):
    """Accessing protected backend route without auth must redirect to login."""
    page.goto(f"{BACKEND}/product/index", timeout=15000)
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator('input[name="LoginForm[username]"]')).to_be_visible()
