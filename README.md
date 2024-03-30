Prpgram to turn Debian into a NAS. This program utilizes sshfs over smb and nfs, the pro's of this protocol are:

1) Allowing easier server/client connection configuration for backing up files.
2) Provides a secure connection by default.

Tests done to compare the R/W speed between sshfs, nfs, and smb show that sshfs, while not being the fastest, doesn't seem to be much slower either: 
