// subscriber.js
const mqtt = require('mqtt');
const mysql = require('mysql');
const moment = require('moment');

// Koneksi MQTT broker (misalnya localhost)
const client = mqtt.connect('mqtt://localhost');

// Koneksi ke MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'presensi_db'
});

db.connect(err => {
    if (err) throw err;
    console.log('Terhubung ke database MySQL.');
});

client.on('connect', () => {
    console.log('Terhubung ke MQTT broker.');
    client.subscribe('absen');
});

client.on('message', (topic, message) => {
    const rfid = message.toString();
    console.log(`RFID diterima: ${rfid}`);

    // Simpan RFID ke buffer untuk form tambah mahasiswa
    const insertBuffer = "INSERT INTO rfid_buffer (rfid) VALUES (?)";
    db.query(insertBuffer, [rfid], (err) => {
        if (err) {
            console.error('❌ Gagal simpan ke buffer RFID:', err);
        } else {
            console.log('✅ RFID disimpan ke buffer.');
        }
    });

    // Query untuk mengecek apakah RFID ada di tabel siswa_tb
    const query = "SELECT * FROM siswa_tb WHERE rfid = ?";
    db.query(query, [rfid], (err, results) => {
        if (err) return console.error(err);

        if (results.length > 0) {
            const nama = results[0].nama;
            const tanggal = moment().format('YYYY-MM-DD');
            const jam = moment().format('HH:mm:ss');

            const insert = "INSERT INTO presensi_tb (rfid, tanggal, jam) VALUES (?, ?, ?)";
            db.query(insert, [rfid, tanggal, jam], (err2) => {
                if (err2) return console.error(err2);
                console.log("Presensi disimpan.");
                client.publish('pesanbalik', JSON.stringify({ Nama: nama, Sukses: true }));
            });
        } else {
            console.log("RFID tidak ditemukan.");
            client.publish('pesanbalik', JSON.stringify({ Gagal: true }));
        }
    });
});
