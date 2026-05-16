from playwright.sync_api import Page


def backend_login(page: Page, username: str = "harut", password: str = "admin123") -> None:
    page.goto("http://localhost:8090")
    page.fill('input[name="LoginForm[username]"]', username)
    page.fill('input[name="LoginForm[password]"]', password)
    page.click('button[type="submit"]')
    page.wait_for_url("**/am**", timeout=10000)
