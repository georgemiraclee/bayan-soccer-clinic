<?php
namespace App\Helpers;

class WhatsAppTemplates
{
    public static function ssbRegistrationLink($namaSSB, $userLink, $pic = null)
    {
        $greeting = $pic ? "Halo {$pic}," : "Halo,";
        
        return "🏈 *PENDAFTARAN SSB - TURNAMEN SEPAK BOLA*\n\n" .
               "{$greeting}\n\n" .
               "Kami dengan senang hati mengundang *{$namaSSB}* untuk berpartisipasi dalam turnamen sepak bola yang akan segera diselenggarakan.\n\n" .
               "📋 *Link Pendaftaran Khusus SSB Anda:*\n" .
               "{$userLink}\n\n" .
               "🎯 *Kategori yang Tersedia:*\n" .
               "• 7-8 Tahun\n" .
               "• 9-10 Tahun\n" .
               "• 11-12 Tahun\n\n" .
               "⚠️ *Penting:*\n" .
               "- Gunakan link di atas untuk mendaftarkan pemain dari SSB Anda\n" .
               "- Pastikan data yang diisi lengkap dan benar\n" .
               "- Kuota terbatas, daftar segera!\n\n" .
               "Jika ada pertanyaan, silakan hubungi panitia.\n\n" .
               "Terima kasih! 🙏\n" .
               "*Panitia Turnamen*";
    }

    public static function quotaReminder($namaSSB, $sisaKuota, $userLink)
    {
        return "🔔 *REMINDER KUOTA SSB*\n\n" .
               "Halo *{$namaSSB}*,\n\n" .
               "Sisa kuota SSB Anda: *{$sisaKuota} slot*\n\n" .
               "Segera daftarkan pemain Anda sebelum kuota habis!\n\n" .
               "Link pendaftaran: {$userLink}\n\n" .
               "Terima kasih! 🙏";
    }

    public static function quotaFull($namaSSB)
    {
        return "✅ *KUOTA PENUH*\n\n" .
               "Selamat! SSB *{$namaSSB}* telah mencapai kuota maksimal.\n\n" .
               "Terima kasih atas partisipasinya dalam turnamen ini.\n\n" .
               "Sampai jumpa di lapangan! ⚽";
    }
}