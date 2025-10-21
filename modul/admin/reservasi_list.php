<?php
// File: modul/admin/reservasi list.php
session_start();

// Cek otentikasi admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login-admin.php");
    exit();
}

// Include koneksi DB
include_once '../../lib/koneksi.php'; 

// Pastikan parameter ID dan action tersedia
if (isset($_GET['id']) && isset($_GET['action'])) {
    $reservasi_id = intval($_GET['id']);
    $action = strtolower($_GET['action']);
    $new_status = '';
    
    // Tentukan status baru berdasarkan list
    if ($action == 'confirm') {
        $new_status = 'confirmed';
    } elseif ($action == 'cancel') {
        $new_status = 'cancelled';
    } else {
        // Jika list tidak valid, kembali ke dashboard
        header("Location: dashboard.php");
        exit();
    }

    // Query untuk update status
    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $reservasi_id);
    
    if ($stmt->execute()) {
        // Berhasil update
        $_SESSION['status_msg'] = "Reservasi ID {$reservasi_id} berhasil diubah statusnya menjadi " . ucfirst($new_status) . ".";
        $_SESSION['status_type'] = "success";
    } else {
        // Gagal update
        $_SESSION['status_msg'] = "Gagal mengubah status reservasi: " . $conn->error;
        $_SESSION['status_type'] = "danger";
    }
    
    $stmt->close();
}

$conn->close();

// Selalu redirect kembali ke dashboard setelah list selesai
header("Location: dashboard.php");
exit();
?>