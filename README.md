# Summary:

TurtleNAS :turtle: turns Debian into a NAS, to deliver files securely in it's shell!

Designed with a web GUI and an easily configurable system for adding clients for file backup and restoration. This program provides user-file downloading from a Web-Interface, an easy to use setup and configuration menu, and security with SSH/HTTPS connections and the built-in linux PAM system for account management.

`sshfs` is utilized over `smb` and `nfs`, the direct pro's of this protocol are:

  1) Allowing easier server/client connection configuration for backing up files.
  2) Provides a secure connection through SSH with key access to backup-clients by default.

Tests done to compare the R/W speed between the three show that while `sshfs` is not the fastest option, it still has great speed!: https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html. This draw-back is minor in my opinion, when understanding the perks that come in the form of simplicity, usablity, and security.

# Install:
> [!IMPORTANT]
> Recommeneded on fresh install of latest Debian Stable, with no Desktop Environment installed. While this program may work on other configurations, i'll only be maintaining it from Debian (Stable) 12.5.0 and up.

  1) Download this program. (edit the command here once uploaded officaially.)
  2) Enter root terminal and type `bash path/to/files/setup.sh`.
  3) Go through the setup. Packages will be ask to install and configuartion will take place. You'll also be asked to setup a password for the admin account (make it secure and remember it, you'll log in to the browser with this.) and sign an SSL cert.
  4) After the setup finishes with no errors, reboot the system.

# Post-Install & Usage:

Open the server's IP or Hostname in an alternate browser (*example:* https://192.168.0.20), login to the web portal with the admin account using the password you created during install. You may use this to create/delete new users, edit permissions, add connections, and more!

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

