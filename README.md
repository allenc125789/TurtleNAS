Prpgram to turn Debian into a NAS. Designed with a web-interface and an easily configurable system for adding clients for file backup. Currently this is a project to further streamline and test a previous proof-of-concept script I made on utilizing alternative FTP protocols.

This program utilizes sshfs over smb and nfs, the pro's of this protocol are:

1) Allowing easier server/client connection configuration for backing up files.
2) Provides a secure connection through SSH to backup-clients by default.

Tests done to compare the R/W speed between sshfs, nfs, and smb show that sshfs, while not being the fastest, doesn't seem to be much slower either: https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html. This draw-back is minor in my opinion, when understanding the perks that come in the form of simplicity and usablity.

Install:

1) Download this program's zip and Extract the files.
2) Open the terminal and type sudo path/to/files/setup.sh.
3) Go through the setup options. You'll be asked to setup passwords and an SSL cert.
4) After the script finishes with no errors, reboot the system.

Post-Install:

1) Open the server's IP or Hostname in an alternate browser, login with the admin's
