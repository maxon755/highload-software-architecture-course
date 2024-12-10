FROM fabiocicerchia/nginx-lua

WORKDIR /root

RUN (curl -s https://api.github.com/repos/magiclen/nginx-cache-purge/releases/latest | sed -r -n 's/.*"browser_download_url": *"(.*\/nginx-cache-purge_'$(uname -m)')".*/\1/p' | wget -i -)  \
    && mv nginx-cache-purge_$(uname -m) /usr/local/bin/nginx-cache-purge \
    && chmod +x /usr/local/bin/nginx-cache-purge

#RUN apk add --no-cache rust cargo git

#RUN git clone https://github.com/magiclen/nginx-cache-purge.git nginx-cache-purge
#RUN cd nginx-cache-purge \
#    && cargo build \
#    && cp target/debug/nginx-cache-purge /usr/local/bin/

CMD ["nginx", "-g", "daemon off;"]
