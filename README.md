> [!NOTE]
> **Current Status:** Under Development. The Web-GUI based File Browser of the application is almost complete in terms of functionality. Files can be uploaded, removed, downloaded, etc. to the Debian server from the admin account, or an account made with `create-user.sh`.
>
> I'll be constructing the admin page, an automated backup system, cleaning code, and revaluating functionality/methodology as soon as possible.
>
[Update-logs](https://github.com/allenc125789/TurtleNAS/tree/main/docs/update-logs),
[Planned Updates](https://github.com/allenc125789/TurtleNAS/blob/main/docs/update-logs/Planned-Updates.md),
[Work-Flow](https://github.com/allenc125789/TurtleNAS/blob/main/docs/images/project-tracking/TurtleNAS-FlowChart.png)


# Overview:

![TurtleNAS Preview](https://github.com/allenc125789/TurtleNAS/blob/main/docs/images/screenshots/turtlenas-preview.gif)

An Open-Source NAS program based on Debian. Offers a web-GUI, which includes a file browser and an easily configurable system for accessing local clients to backup and restore files/folders.

### Features
>   + [Authentication.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/authentication.md)
>   + [File Browser.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/file-browser.md)
>   + [Compatibility.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/compatibility.md)

# Installation:
> [!IMPORTANT]
> **[> Requirements](https://github.com/allenc125789/TurtleNAS/blob/main/docs/requirements.md)**
>
> **Recommeneded on fresh install of latest Debian Stable, with no Desktop Environment configured.** While this program may work on other configurations, all development so far has been done on this setup.
>

### Source
  1) Install Debian and the `git` package.
  2) Download the program and run the setup with this command as root: `git clone https://allenc125789:@github.com/allenc125789/TurtleNAS.git && bash ./TurtleNAS/setup.sh`
  3) Go through the setup steps.
     + Installation of dependancies (`y` to install and continue).
     + Create a new password for the admin account **(make it secure and remember it, you'll log in to the browser with this.)**
     + Signing an SSL certificate.
  4) After the setup finishes with no errors, enter `sudo reboot` and wait for the system to reboot.

# Post-Install & Usage:

Open the server's IP or Hostname in an alternate browser (*example: https://192.168.0.20*), login to the web portal with the password you made on install. Username is `admin`.

**If you encounter any errors or issues, please refer to the [User Manual](https://github.com/allenc125789/TurtleNAS/blob/main/docs/user-manual.md), or make an issue request.**


