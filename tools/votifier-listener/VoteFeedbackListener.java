import com.vexsoftware.votifier.model.Vote;
import com.vexsoftware.votifier.model.VoteListener;

/**
 * Classic Votifier vote listener for end-to-end testing.
 *
 * Votifier only calls this AFTER it has successfully decrypted a vote, so seeing
 * the message is proof the server accepted the packet (not just that the client
 * sent one). It broadcasts the vote to in-game chat, which also echoes to the
 * server console (visible via `docker compose logs -f spigot`); if Bukkit isn't
 * available it falls back to printing to the console.
 *
 * Build with the Votifier jar on the classpath and drop the resulting .class into
 * plugins/Votifier/listeners/ (see tools/votifier-listener/build.sh).
 */
public class VoteFeedbackListener implements VoteListener {

    public void voteMade(Vote vote) {
        String message = "[VoteFeedback] vote received -> " + vote;

        // Broadcast to in-game chat. Bukkit.broadcastMessage() also echoes to the
        // server console, so this is the single output. Done reflectively so the
        // listener compiles against only the Votifier jar. If Bukkit isn't available,
        // fall back to a plain console line (avoids printing the message twice).
        try {
            Class<?> bukkit = Class.forName("org.bukkit.Bukkit");
            bukkit.getMethod("broadcastMessage", String.class).invoke(null, message);
        } catch (Throwable noBukkit) {
            System.out.println(message);
        }
    }
}
