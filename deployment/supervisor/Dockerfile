FROM mini-crm-backend

ARG ENABLE_SUPERVISOR=false
RUN if [ "$ENABLE_SUPERVISOR" = "true" ]; then \
        apt-get update && apt-get install -y supervisor; \
    fi

CMD ["supervisord", "-n"]
