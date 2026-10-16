"""
E2E tests: the admin panel is closed to guests and customers.
Regression for the 2026-10 audit: any customer account could log in to the
backend, and most admin controllers had no access rules at all.
"""
import pytest
from playwright.sync_api import Page, expect

BACKEND = "http://localhost:8090"
CUST_USER, CUST_PASS = "e2etester", "test123"   # role 20 (customer)

PROTECTED = ["/am/user/index", "/am/auction/index", "/am/team/index",
             "/am/sitesettings/index", "/am/tr-product/update?id=10", "/am/gii"]


@pytest.mark.parametrize("path", PROTECTED)
def test_guest_redirected_to_login(page: Page, path: str):
    page.goto(BACKEND + path, timeout=15000)
    assert "/site/login" in page.url, f"guest reached {path}: {page.url}"
    expect(page.locator('input[name="LoginForm[username]"]')).to_be_visible()


def test_customer_cannot_log_in(page: Page):
    page.goto(BACKEND + "/am/site/login", timeout=15000)
    page.fill('input[name="LoginForm[username]"]', CUST_USER)
    page.fill('input[name="LoginForm[password]"]', CUST_PASS)
    page.click('button[type="submit"]')
    page.wait_for_load_state("domcontentloaded")
    expect(page.locator("body")).to_contain_text("Incorrect username or password")
    page.goto(BACKEND + "/am/user/index")
    assert "/site/login" in page.url, f"customer reached admin: {page.url}"
