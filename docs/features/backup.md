# Backups:
Backups are made easy, through the use of the protocol SSHFS. This protocol makes the process of registering a client computer that you would like to backup simpler, with connections being established server-side, provided the correct SSH credentials.

This feature can be found and configured on the Admin page.

## Tests & Comparisons (SSHFS, NFS, SMB):
Research done by a user named Jakeler[^1] tests and compares the R/W speed between the three most common file-sharing protocols. `sshfs` (Blue) is shown to be the fastest option, with encrypted transportation.[^2] 

![](https://github.com/allenc125789/TurtleNAS/blob/main/extra/Screenshot%20from%202024-04-02%2023-37-15.png)

## References:
[^1]: https://github.com/Jakeler
[^2]: https://blog.ja-ke.tech/2019/08/27/nas-performance-sshfs-nfs-smb.html
