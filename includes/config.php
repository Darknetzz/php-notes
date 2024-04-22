<?php

/* ───────────────────────────────────────────────────────────────────── */
/*                                Markdown                               */
/* ───────────────────────────────────────────────────────────────────── */
define("STRICT_LINEBREAK", False); # False: "\n" is converted to "\n\n" when viewing notes

/* ────────────────────────────────────────────────────────────────────────── */
/*                                    Notes                                   */
/* ────────────────────────────────────────────────────────────────────────── */
define("NOTES_DIR", "notes");
define("NOTES_META_FILE", "notes.json");
define("NOTES_META_FILE_PATH", NOTES_DIR."/".NOTES_META_FILE);