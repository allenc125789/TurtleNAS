> [!NOTE]
> **Current Status:** Under Development. The Web-GUI based File Browser of the application is almost complete in terms of functionality. Files can be uploaded, removed, downloaded, etc. to the Debian server from the admin account, or an account made with `create-user.sh`.
>
> I'll be constructing the admin page, an automated backup system, cleaning code, and revaluating functionality/methodology as soon as possible.
>
[Update-logs](https://github.com/allenc125789/TurtleNAS/tree/main/extra/update-logs),
[Planned Updates](https://github.com/allenc125789/TurtleNAS/blob/main/extra/update-logs/Planned-Updates),
[Work-Flow](https://github.com/allenc125789/TurtleNAS/blob/main/extra/TurtleNAS-FlowChart.png)


# About:


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ❤

:desktop_computer:. . .:turtle:. . .:desktop_computer:

An Open-Source NAS system based on Debian. Offers a web-GUI file browser and an easily configurable system for adding clients and files for quick backup and restoration.

# Install:

## From Source:
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


