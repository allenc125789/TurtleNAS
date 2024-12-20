> [!NOTE]
> **Current Status:** Under Development. Doing the admin pages.
>
> (Working on admin->network->interfaces.php) Setting up the page to be a list similar to the browser page. With the ability to select and deselect which interfaces are up or down. As well as the ability to change the interface's setting's (IP address, netmask, etc.)
>
[Update-logs](https://github.com/allenc125789/TurtleNAS/blob/dev/docs/update-logs/Change-Logs.md),
[Planned Updates](https://github.com/allenc125789/TurtleNAS/blob/main/docs/update-logs/Planned-Updates.md),
[Work-Flow](https://github.com/allenc125789/TurtleNAS/blob/main/docs/images/project-tracking/TurtleNAS-FlowChart.png)


# Overview:

![TurtleNAS Preview](https://github.com/allenc125789/TurtleNAS/blob/main/docs/images/screenshots/turtlenas-preview.gif)

A simple Open-Source NAS program for Debian. Offers a web-GUI, which includes a file browser and an easily configurable system for accessing local clients to backup and restore files/folders.


### Features

>   + [Authentication.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/authentication.md)
>   + [Web-Based File Browser.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/file-browser.md)
>   + [Admin Resources.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/admin.md)
>   + [Compatibility.](https://github.com/allenc125789/TurtleNAS/blob/main/docs/features/compatibility.md)

# Installation:
> [!IMPORTANT]
> **[> Requirements](https://github.com/allenc125789/TurtleNAS/blob/main/docs/requirements.md)**
>
> **Recommeneded on fresh install of latest Debian Stable, with no Desktop Environment configured.** While this program may work on other configurations, all development so far has been done on this setup.
>

## Install (Source)
<details>

+ **Run the following as Root**

  1) Install Debian and the `git` package.
     + `apt install git`
       
  3) Download the program and run the setup with this command:
     + `git clone https://allenc125789:@github.com/allenc125789/TurtleNAS.git && bash ./TurtleNAS/setup.sh`
       
  4) Read through the setup steps and enter the necessary information when presented.
     + Installation of dependancies.
       + (`y` to install and continue)
     + Create a new password for the admin account.
       + **(make it secure and remember it, you'll log in to the browser with this.)**
     + Signing an SSL certificate.
       + (For home use, you can press the `Enter` key to skip these fields)
       
  5) After the setup finishes with no errors, enter `sudo reboot` and wait for the system to reboot.

</details>


# Post-Install & Usage:

Open the server's IP or Hostname in an alternate browser (*example: https://192.168.0.20*), login to the web portal with the password you made on install. Username is `admin`.

**If you encounter any errors or issues, please refer to the [User Manual](https://github.com/allenc125789/TurtleNAS/blob/main/docs/user-manual.md), or make an issue request.**


