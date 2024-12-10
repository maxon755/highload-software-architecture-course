#!/usr/bin/env bash

set -e

USER=$1
NEW_UID=$2
NEW_GID=$3

USER_UID=$(id -u "$USER")
USER_GID=$(id -g "$USER")

function change_uid ()
{
    OLD=$1;
    NEW=$2;
    usermod -u "$NEW" "$(id -un "$OLD")"
    find / -xdev -user "$OLD" -exec chown -h "$NEW" {} \;
}

function change_gid ()
{
    OLD=$1;
    NEW=$2;
    groupmod -g "$NEW" "$(getent group "$OLD" | cut -d: -f1)"
    find / -xdev -group "$OLD" -exec chgrp -h "$NEW" {} \;
}


if [ "$USER_UID" -ne "$NEW_UID" ]; then
    echo "Changing UID $USER_UID to $NEW_UID"
    change_uid "$USER_UID" "$NEW_UID"
fi

if [ "$USER_GID" -ne "$NEW_GID" ]; then
    echo "Changing GID $USER_GID to $NEW_GID"
    change_gid "$USER_GID" "$NEW_GID"
fi
