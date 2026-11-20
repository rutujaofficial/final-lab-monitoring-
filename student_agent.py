"""
Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
Department of Electronics and Computer Engineering
Smart Digital Lab Monitoring System - Student PC Monitoring Agent

This Windows Python agent checks active window titles every 5 seconds
against the academic whitelist, updates status, and synchronizes
in real time with the central laboratory server.
"""

import sys
import time
from datetime import datetime
import requests

try:
    import pygetwindow as gw
except ImportError:
    print("[ERROR] 'pygetwindow' library is required. Please install via: pip install pygetwindow")
    sys.exit(1)

# Default Server Endpoint (can be overridden with InfinityFree live domain at launch)
DEFAULT_API_URL = "http://localhost/smart_lab/api_update.php"

# Academic Whitelist as specified in lab policy
# Whitelist items: VS Code, Python, XAMPP, Chrome - localhost, phpMyAdmin, Sublime, Dev-C++
ACADEMIC_WHITELIST = [
    "visual studio code",
    "vs code",
    "code",
    "python",
    "idle",
    "xampp",
    "chrome - localhost",
    "localhost",
    "127.0.0.1",
    "smart_lab",
    "phpmyadmin",
    "sublime",
    "sublime text",
    "dev-c++",
    "dev c++",
    "devcpp",
    # System & essential student tools
    "command prompt",
    "powershell",
    "windows terminal",
    "student lab portal",
    "zcoer",
    "smart digital lab"
]

def print_banner():
    print("=" * 68)
    print("  ZEAL COLLEGE OF ENGINEERING & RESEARCH (ZCOER), PUNE-41")
    print("  Department of Electronics & Computer Engineering")
    print("  Smart Digital Lab Monitoring System - Windows Agent v2.0")
    print("=" * 68)
    print("  Academic Whitelist Policy Active:")
    print("  - Visual Studio Code / VS Code")
    print("  - Python IDLE & Interpreters")
    print("  - XAMPP Control Panel")
    print("  - Chrome (Localhost / Lab Portals)")
    print("  - phpMyAdmin")
    print("  - Sublime Text")
    print("  - Dev-C++")
    print("=" * 68)

def is_whitelisted(window_title: str) -> bool:
    """Check if the active window title matches the academic whitelist."""
    if not window_title:
        return True  # Desktop or transitioning

    title_lower = window_title.lower()

    # Desktop / File Explorer idle
    if title_lower in ["program manager", "windows desktop", "desktop", ""]:
        return True

    for term in ACADEMIC_WHITELIST:
        if term in title_lower:
            return True

    return False

def get_active_window_title() -> str:
    """Retrieve the title of the current foreground window."""
    try:
        active_window = gw.getActiveWindow()
        if active_window and active_window.title:
            return active_window.title.strip()
    except Exception as e:
        return f"Unknown ({e})"
    return "Desktop"

def main():
    print_banner()

    # Optional server URL input (supports InfinityFree live domain or localhost)
    print(f"\n[CONFIG] Default Server: {DEFAULT_API_URL}")
    server_choice = input("[PROMPT] Enter InfinityFree/Server URL (Press Enter for default): ").strip()
    if server_choice:
        if not server_choice.startswith("http://") and not server_choice.startswith("https://"):
            server_choice = "http://" + server_choice
        if not server_choice.endswith("api_update.php"):
            server_choice = server_choice.rstrip("/") + "/api_update.php"
        api_url = server_choice
    else:
        api_url = DEFAULT_API_URL

    # Prompt student for their Roll Number
    roll_no = ""
    while not roll_no:
        try:
            roll_no = input("\n[PROMPT] Enter Student Roll Number (e.g., EC2203): ").strip().upper()
            if not roll_no:
                print("  [!] Roll number cannot be empty.")
        except (KeyboardInterrupt, EOFError):
            print("\n[INFO] Exiting agent.")
            sys.exit(0)

    print(f"\n[INFO] Initializing monitoring session for Roll No: {roll_no}")
    print(f"[INFO] Server Endpoint: {api_url}")
    print("[INFO] Polling interval: 5 seconds. Press Ctrl+C to terminate session.\n")
    print("-" * 68)

    session_active = True
    while session_active:
        try:
            window_title = get_active_window_title()

            # Verify against academic whitelist
            if is_whitelisted(window_title):
                status = "Active"
                status_display = "[ACTIVE]            "
            else:
                status = "Alert: Unauthorized App"
                status_display = "[ALERT: UNAUTHORIZED]"

            # Truncate title for clean database storage & display
            safe_title = window_title[:250] if len(window_title) > 250 else window_title

            payload = {
                "roll_no": roll_no,
                "status": status,
                "current_window": safe_title
            }

            now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

            # Post data to PHP/MySQL API
            try:
                response = requests.post(api_url, json=payload, timeout=5)
                if response.status_code == 200:
                    data = response.json()
                    sync_note = "Synced" if data.get("success") else data.get("message", "Error")
                else:
                    sync_note = f"HTTP {response.status_code}"
            except requests.exceptions.RequestException as req_err:
                sync_note = f"Connection Failed ({type(req_err).__name__})"

            print(f"[{now_str}] {status_display} | Window: {safe_title[:38]:<38} | Server: {sync_note}")

            # 5-second interval as specified
            time.sleep(5)

        except KeyboardInterrupt:
            print("\n" + "-" * 68)
            print("[INFO] Termination signal received. Sending session end status...")
            try:
                requests.post(api_url, json={
                    "roll_no": roll_no,
                    "status": "Inactive",
                    "current_window": "Logged Out"
                }, timeout=3)
            except Exception:
                pass
            print("[INFO] Monitoring session ended. Goodbye!")
            session_active = False
            break
        except Exception as e:
            print(f"[ERROR] Loop exception: {e}")
            time.sleep(5)

if __name__ == "__main__":
    main()
