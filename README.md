> [!NOTE]
> **Current Status:** Under Development. The Web-GUI based File Browser of the application is almost complete in terms of functionality. Files can be uploaded, removed, downloaded, etc. to the Debian server from the admin account, or an account made with `create-user.sh`.
>
> I'll be constructing the admin page, an automated backup system, cleaning code, and revaluating functionality/methodology as soon as possible.
>
[Update-logs](https://github.com/allenc125789/TurtleNAS/tree/main/extra/update-logs),
[Planned Updates](https://github.com/allenc125789/TurtleNAS/blob/main/extra/update-logs/Planned-Updates),
[Work-Flow](https://github.com/allenc125789/TurtleNAS/blob/main/extra/TurtleNAS-FlowChart.png)

# Summary:

*TurtleNAS for Debian, to help deliver files in it's secure shell!‎*

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ❤

:desktop_computer:. . .:turtle:. . .:desktop_computer:

### About:

Designed with a web GUI and an easily configurable system for adding clients for file backup and restoration. This program provides: an easy to use setup system, a configuration menu for admins, a download page for users, security with SSH/HTTPS connections, and the built-in linux PAM system for account management.

`sshfs` is utilized over `smb` and `nfs`, the direct pro's of this protocol are:

  - Allowing easier server/client connection configuration for backing up files.
  - Provides a secure connection through SSH with key access to backup-clients, by default.
  - Fastest option of encrypted file transfer protocols.

### Tests (SSHFS, NFS, SMB):

Research done by a user named Jakeler[^1] tests and compares the R/W speed between the three most common file-sharing protocols. `sshfs` (Blue) is shown to be the fastest option, with encrypted transportation.[^2] 

![](https://github.com/allenc125789/TurtleNAS/blob/main/extra/Screenshot%20from%202024-04-02%2023-37-15.png)

# Install:
> [!IMPORTANT]
> **Recommeneded on fresh install of latest Debian Stable, with no Desktop Environment configured.** While this program may work on other configurations, i'll only be maintaining it from Debian (Stable) 12.5.0 and up on this setup.

  1) Install Debian and the `git` package.
  2) Copy and paste this command as root: `git clone https://allenc125789:@github.com/allenc125789/TurtleNAS.git && bash ./TurtleNAS/setup.sh`
  3) Go through the setup. Dependancies will be installed and configuartion will take place. You'll also be asked to setup a password for the admin account **(make it secure and remember it, you'll log in to the browser with this.)** and sign an SSL cert.
  4) After the setup finishes with no errors, enter `sudo reboot` and wait for the system to reboot.

# Post-Install & Usage:

> [!NOTE]
> This application is under development and NOT currently designed yet to be public facing, only for use in a internal network. After all the essential functions have been designed, I will be testing it's security more in depth.

Open the server's IP or Hostname in an alternate browser (*example: https://192.168.0.20*), login to the web portal with the password you made on install. Username is `admin`.

If you encounter any errors or issues, please refer to the [User Manual](https://github.com/allenc125789/TurtleNAS/blob/main/extra/User-Manual.md), or make a issue request.

[^1]: https://github.com/Jakeler
[^2]: https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html

>
**References:**
