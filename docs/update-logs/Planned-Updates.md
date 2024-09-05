~ need to create js based timeout function.

~ lots of unused code needs to be trimmed. might need to reuse the hashing system to provide a more unique hash when downloading a compressed folder.

~ re-evaluate permissions method. Consideration for R/W abalities, sudo, and grouping needed.

~ currently, there is no limit for how much data can be uploaded to the server. When creating the admin menu, I'll be adding a function to limit file uploads per user based on disk availability. I'll also probably need to add notifications for when disk's are becoming full.

~ notifications for `login.html`. Currently it does not tell you if you got a password wrong, or if something else is happpening to the server.

~ Need to create an IP based-blacklist logging and banning system. Probably by a bash or php based function logging into mariadb

~ Create a dockerfile for easier deployment

~ fix the hotbar of the browser. Currently it is a bit buggy. Will probably need a seperate div on the table item.
