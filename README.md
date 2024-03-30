Summary:

Program that turns Debian into a NAS. Designed with a web-interface and an easily configurable system for adding clients for file backup. A continuation to my previous script acting as a proof-of-concept in using alternative FTP methods.

This program provides a web-interface for configuration and user-file downloading, an easy setup, and security with SSH, HTTPS connections and the built-in linux PAM system for account management.

sshfs is utilized over smb and nfs, the direct pro's of this protocol are:

1) Allowing easier server/client connection configuration for backing up files.
2) Provides a secure connection through SSH to backup-clients by default.

Tests done to compare the R/W speed between sshfs, nfs, and smb show that while sshfs is not the fastest option, it still has great speed: https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html. This draw-back is minor in my opinion, when understanding the perks that come in the form of simplicity, usablity, and security.

Install:

1) Download this program.
2) Open the terminal and type sudo path/to/files/setup.sh.
3) Go through the setup. Packages will be installed. You'll also be asked to setup a password (make it secure and remember it, this should be a mostly passive account) and an SSL cert.
4) After the setup finishes with no errors, reboot the system.

Post-Install:

1) Open the server's IP or Hostname in an alternate browser (exp: https://192.168.0.20), login to the web portal with the sysadmin account and the password you created during install.
2) Create a new admin account for better security. Logout and login to this newly made account.

