FROM telegraf

ARG DOCKER_GID=963

ADD .docker/services/bin /usr/local/bin/

# sync www-data uid/gid with host user
RUN /usr/local/bin/sync-user-id.sh telegraf 999 $DOCKER_GID
