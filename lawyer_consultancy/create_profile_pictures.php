
<?php
$lawyer_images = [
    'ahmed_raza_khan.jpg',
    'saqib_ali_shah.jpg',
    'samina_farooq.jpg',
    'ayesha_rehman_khan.jpg',
    'fariha_zainab_siddiqui.jpg',
    'shabana_yousaf.jpg',
    'sadia_iqbal_chaudhry.jpg',
    'amina_javed_malik.jpg',
    'faizan_mahmood.jpg',
    'mohsin_zafar_qureshi.jpg',
    'irfan_nawaz_abbasi.jpg',
    'waqas_murtaza_chishti.jpg',
    'rabia_qazi.jpg',
    'mehrun_nisa_kazmi.jpg',
    'huma_shahzad.jpg',
    'zoya_noreen_sheikh.jpg',
    'rashid_umar_siddiqui.jpg',
    'tariq_hussain_khokhar.jpg',
    'adnan_jameel_bhatti.jpg',
    'asif_rehman_malik.jpg',
    'ahmed_hassan.jpg',
    'salman_akram.jpg',
    'fatima_noor.jpg',
    'muhammad_anwar.jpg',
    'ali_raza_chohan.jpg',
    'nadia_waheed.jpg',
    'imran_shah.jpg',
    'zainab_malik.jpg',
    'kashif_mahmood.jpg',
];

$upload_dir = __DIR__ . '/uploads/profile_pictures/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

foreach ($lawyer_images as $image) {
    echo "<li>" . htmlspecialchars($image) . "</li>";
}

?>
