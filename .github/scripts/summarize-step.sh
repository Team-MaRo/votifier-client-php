#!/usr/bin/env bash
# Run a CI command and stream its stdout+stderr to BOTH the workflow's
# job log (live) and `$GITHUB_STEP_SUMMARY` (after the fact, with a
# status-aware section heading). Preserves the command's exit code so
# the calling step's `continue-on-error: true` records the real outcome
# — letting a trailing gate step re-derive the job's pass/fail without
# losing the per-step output.
#
# Section heading carries a leading status glyph (green-check on pass,
# red-cross on fail) so you can scan the job summary and spot the
# failing step at a glance. The <details> block defaults closed on
# success (passing checks don't drown the summary) and open on failure
# (failing output is visible without a click).
#
# Usage: bash .github/scripts/summarize-step.sh "Title" cmd arg1 arg2 ...

set -o pipefail

TITLE="$1"
shift

LOG="$(mktemp)"
trap 'rm -f "$LOG"' EXIT

"$@" 2>&1 | tee "$LOG"
EC=${PIPESTATUS[0]}

if [ "$EC" -eq 0 ]; then
  STATUS="✅ Passed"
  DETAILS_OPEN=""
else
  STATUS="❌ Failed (exit $EC)"
  DETAILS_OPEN=" open"
fi

{
  echo
  echo "### $STATUS — $TITLE"
  echo
  echo "<details${DETAILS_OPEN}><summary>Output</summary>"
  echo
  echo '```text'
  cat "$LOG"
  echo '```'
  echo
  echo '</details>'
} >> "$GITHUB_STEP_SUMMARY"

exit "$EC"
