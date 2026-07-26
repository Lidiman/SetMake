#!/usr/bin/env python3
"""
CLI wrapper for ytmusicapi + yt-dlp.
Communicates with Laravel via JSON on stdout/stderr.

Usage:
  python ytmusic_wrapper.py search <query>
  python ytmusic_wrapper.py get-song <videoId>
  python ytmusic_wrapper.py download-audio <videoId> <output_path>
  python ytmusic_wrapper.py auth-status

All commands except `download-audio` output JSON to stdout.
Error messages go to stderr and exit code is non-zero on failure.
"""

import json
import os
import subprocess
import sys
import tempfile
from pathlib import Path

try:
    from ytmusicapi import YTMusic
except ImportError:
    print(json.dumps({"error": "ytmusicapi not installed. Run: pip install ytmusicapi"}))
    sys.exit(1)


def get_oauth_path():
    """Resolve oauth.json path. Checks env var, then project root, then default."""
    env_path = os.environ.get("YTMUSIC_OAUTH_PATH")
    if env_path:
        return Path(env_path)

    # Look relative to this script's directory
    script_dir = Path(__file__).resolve().parent
    project_root = script_dir.parent
    candidates = [
        project_root / "oauth.json",
        script_dir / "oauth.json",
        Path.home() / ".config" / "ytmusicapi" / "oauth.json",
    ]
    for p in candidates:
        if p.exists():
            return p

    return None


def get_ytm():
    """Initialize YTMusic client."""
    oauth_path = get_oauth_path()
    if oauth_path and oauth_path.exists():
        # Headers file is needed for ytmusicapi < 2.0
        return YTMusic(str(oauth_path))
    return YTMusic()


def cmd_search(args):
    """Search YouTube Music for songs."""
    query = " ".join(args)
    if not query:
        print(json.dumps({"error": "Search query is required"}))
        sys.exit(1)

    try:
        yt = get_ytm()
        results = yt.search(query, filter="songs", limit=10)
        songs = []
        for r in results:
            song = {
                "videoId": r.get("videoId"),
                "title": r.get("title", ""),
                "artists": [
                    a.get("name", "") for a in (r.get("artists") or [])
                ],
                "album": r.get("album", {}).get("name") if r.get("album") else None,
                "duration": r.get("duration"),
                "duration_seconds": r.get("duration_seconds"),
                "thumbnails": r.get("thumbnails", []),
                "isExplicit": r.get("isExplicit", False),
                "videoType": r.get("videoType"),
            }
            songs.append(song)
        print(json.dumps({"results": songs}))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)


def cmd_get_song(args):
    """Get detailed info about a song by videoId."""
    if not args:
        print(json.dumps({"error": "videoId is required"}))
        sys.exit(1)

    video_id = args[0]
    try:
        yt = get_ytm()
        song = yt.get_song(video_id)
        # get_song returns a dict with 'videoDetails' and other keys
        print(json.dumps(song, default=str))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)


def cmd_download_audio(args):
    """Download audio from a YouTube video using yt-dlp."""
    if len(args) < 2:
        print(json.dumps({"error": "Usage: download-audio <videoId> <output_path>"}))
        sys.exit(1)

    video_id = args[0]
    output_path = args[1]

    url = f"https://www.youtube.com/watch?v={video_id}"
    os.makedirs(os.path.dirname(output_path) or ".", exist_ok=True)

    try:
        result = subprocess.run(
            [
                "yt-dlp",
                "--extract-audio",
                "--audio-format", "mp3",
                "--audio-quality", "0",
                "-o", output_path,
                "--print", "after_move:filepath",
                url,
            ],
            capture_output=True,
            text=True,
            timeout=300,
        )
        if result.returncode != 0:
            print(json.dumps({"error": result.stderr.strip()}))
            sys.exit(1)

        # yt-dlp might add the extension
        actual_path = result.stdout.strip()
        if not actual_path:
            actual_path = output_path

        print(json.dumps({"success": True, "filepath": actual_path}))
    except FileNotFoundError:
        print(json.dumps({"error": "yt-dlp not found. Install it: pip install yt-dlp or brew install yt-dlp"}))
        sys.exit(1)
    except subprocess.TimeoutExpired:
        print(json.dumps({"error": "Download timed out"}))
        sys.exit(1)
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)


def cmd_auth_status(args):
    """Check authentication status."""
    try:
        oauth_path = get_oauth_path()
        yt = get_ytm()
        # Try to make a simple call to verify auth
        try:
            library = yt.get_library_playlists(limit=1)
            status = "authenticated" if oauth_path else "unauthenticated (no oauth.json)"
        except Exception:
            status = "unauthenticated (auth failed)"
        print(json.dumps({
            "status": status,
            "oauth_path": str(oauth_path) if oauth_path else None,
        }))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)


def main():
    if len(sys.argv) < 2:
        print("Usage: ytmusic_wrapper.py <command> [args...]")
        print("Commands: search, get-song, download-audio, auth-status")
        sys.exit(1)

    command = sys.argv[1]
    args = sys.argv[2:]

    commands = {
        "search": cmd_search,
        "get-song": cmd_get_song,
        "download-audio": cmd_download_audio,
        "auth-status": cmd_auth_status,
    }

    if command not in commands:
        print(json.dumps({"error": f"Unknown command: {command}"}))
        sys.exit(1)

    commands[command](args)


if __name__ == "__main__":
    main()
