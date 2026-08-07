<?php
require_once 'config.php';

function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Connexion DB échouée : ' . $conn->connect_error]));
    }
    return $conn;
}

function getStories($conn) {
    $result = $conn->query("SELECT * FROM stories ORDER BY id ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getScene($conn, $storyKey, $sceneKey) {
    $stmt = $conn->prepare("
        SELECT s.*, st.color, st.story_key, st.title AS story_title
        FROM scenes s
        JOIN stories st ON s.story_id = st.id
        WHERE st.story_key = ? AND s.scene_key = ?
        LIMIT 1
    ");
    $stmt->bind_param('ss', $storyKey, $sceneKey);
    $stmt->execute();
    $scene = $stmt->get_result()->fetch_assoc();
    if ($scene) {
        $scene['choices'] = getChoices($conn, $scene['id']);
    }
    return $scene;
}

function getChoices($conn, $sceneId) {
    $stmt = $conn->prepare("
        SELECT * FROM choices
        WHERE scene_id = ?
        ORDER BY sort_order ASC
    ");
    $stmt->bind_param('i', $sceneId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function saveProgress($conn, $sessionId, $storyKey, $sceneKey) {
    $stmt = $conn->prepare("
        INSERT INTO game_sessions (session_id, story_key, current_scene)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
            story_key    = VALUES(story_key),
            current_scene = VALUES(current_scene),
            updated_at   = NOW()
    ");
    $stmt->bind_param('sss', $sessionId, $storyKey, $sceneKey);
    $stmt->execute();
}

function getProgress($conn, $sessionId) {
    $stmt = $conn->prepare("SELECT * FROM game_sessions WHERE session_id = ? LIMIT 1");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function incrementDeaths($conn, $sessionId) {
    $stmt = $conn->prepare("
        UPDATE game_sessions SET death_count = death_count + 1
        WHERE session_id = ?
    ");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
}

function logChoice($conn, $sessionId, $sceneKey, $choiceLabel) {
    $stmt = $conn->prepare("
        INSERT INTO player_history (session_id, scene_key, choice_label)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param('sss', $sessionId, $sceneKey, $choiceLabel);
    $stmt->execute();
}

function resetProgress($conn, $sessionId) {
    $stmt = $conn->prepare("DELETE FROM game_sessions WHERE session_id = ?");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    $stmt2 = $conn->prepare("DELETE FROM player_history WHERE session_id = ?");
    $stmt2->bind_param('s', $sessionId);
    $stmt2->execute();
}

function getPlayerStats($conn, $sessionId) {
    $stmt = $conn->prepare("
        SELECT gs.*, COUNT(ph.id) AS total_choices
        FROM game_sessions gs
        LEFT JOIN player_history ph ON ph.session_id = gs.session_id
        WHERE gs.session_id = ?
        GROUP BY gs.id
    ");
    $stmt->bind_param('s', $sessionId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
