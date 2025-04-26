FROM redis:8.0-M03 AS redis_base
RUN groupadd -g 1000 app && useradd -u 1000 -g app -m app
RUN mkdir -p -m 700 /var/run/redis && chown -R app:app /var/run/redis
RUN mkdir -p -m 700 /data && chown -R app:app /data

USER app
CMD ["redis-server", "--unixsocket", "/var/run/redis/redis.sock", "--unixsocketperm", "777"]
