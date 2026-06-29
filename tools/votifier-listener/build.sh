#!/usr/bin/env bash
# Compile VoteFeedbackListener against the Votifier jar and install the .class
# into the server's listeners folder. Uses a Java 8 JDK in Docker (classic
# Votifier targets Java 8), so no local JDK is needed.
set -euo pipefail
cd "$(dirname "$0")/../.."  # repo root

JAR=$(ls server/plugins/[Vv]otifier*.jar 2>/dev/null | head -1 || true)
if [ -z "$JAR" ]; then
  echo "No Votifier jar found in server/plugins/ — start the server with the plugin first." >&2
  exit 1
fi

mkdir -p server/plugins/Votifier/listeners
docker run --rm -v "$PWD":/work -w /work eclipse-temurin:8-jdk \
  javac -cp "$JAR" -d server/plugins/Votifier/listeners \
  tools/votifier-listener/VoteFeedbackListener.java

echo "Installed VoteFeedbackListener.class -> server/plugins/Votifier/listeners/"
echo "Restart the server (docker compose restart spigot) to load it."
