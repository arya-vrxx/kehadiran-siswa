<?php $menu = $menu ?? ""; ?>
<aside class="sidebar">
    <a href="<?php echo $base; ?>index.php" class="<?php echo $menu == 'beranda' ? 'aktif' : ''; ?>">Beranda</a>
    <a href="<?php echo $base; ?>inimanagekelas/index.php" class="<?php echo $menu == 'kelas' ? 'aktif' : ''; ?>">Kelas</a>
    <a href="<?php echo $base; ?>inimanagesiswa/index.php" class="<?php echo $menu == 'siswa' ? 'aktif' : ''; ?>">Siswa</a>
    <a href="<?php echo $base; ?>inimanagepresensi/index.php" class="<?php echo $menu == 'presensi' ? 'aktif' : ''; ?>">Presensi</a>
</aside>