export PASS=$password SALT='$y$j9T$F31F/jItUvvjOv6IBFNea/$'
perl -le 'print crypt($ENV{PASS}, $ENV{SALT})'
