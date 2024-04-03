# Summary:

*TurtleNAS for Debian, to help deliver files in it's secure shell!‎*

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ❤

:desktop_computer:. . .:turtle:. . .:desktop_computer:

### About:

Designed with a web GUI and an easily configurable system for adding clients for file backup and restoration. This program provides user-file downloading from a Web-Interface, an easy to use setup and configuration menu, security with SSH/HTTPS connections, and the built-in linux PAM system for account management.

`sshfs` is utilized over `smb` and `nfs`, the direct pro's of this protocol are:

  - Allowing easier server/client connection configuration for backing up files.
  - Provides a secure connection through SSH with key access to backup-clients, by default.
  - Fastest form of Encrypted file transfer protocols

### SSHFS Tests:

Tests done to compare the R/W speed between the three show that while `sshfs` (Blue) is not the fastest option (plaintext comparison), it still has [great speeds!](https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html): 
![](https://github.com/allenc125789/TurtleNAS/blob/main/extra/Screenshot%20from%202024-04-02%2023-37-15.png)

This draw-back is minor in my opinion, when understanding the perks that come in the form of simplicity, usablity, and security.

# Install:
> [!IMPORTANT]
> **Recommeneded on fresh install of latest Debian Stable, with no Desktop Environment configured.** While this program may work on other configurations, i'll only be maintaining it from Debian (Stable) 12.5.0 and up.

  1) Download this program. (edit the command here once uploaded officaially.)
  2) `su` to root in terminal and type `bash path/to/files/setup.sh`.
  3) Go through the setup. Packages will be ask to install and configuartion will take place. You'll also be asked to setup a password for the admin account **(make it secure and remember it, you'll log in to the browser with this.)** and sign an SSL cert.
  4) After the setup finishes with no errors, enter `/usr/sbin/reboot` and wait for the system to reboot.

# Post-Install & Usage:

Open the server's IP or Hostname in an alternate browser (*example:* https://192.168.0.20), login to the web portal with the password you made on install. Username `admin`. You may use this to create/delete new users, edit permissions, add connections, and more!

You may also login directly from SSH.


## System
### Users
### Groups
### Settings
## SSHFS Connections
## Storage
## Services
### Cron
### Packages
## Power



### Creating/Removing new SSHFS connections for backup:
### Creating/Removing new Users:
### Downloading/Uploading files from the web:
### Settings (Permission, Grouping, Security, etc...):
### Changing Passwords
### Setting User Permissions
### Setting Web Access Permissions

https://softpanorama.org/Access_control/Sudo/sudoer_file_examples.shtml

