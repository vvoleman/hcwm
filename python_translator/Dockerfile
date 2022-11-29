# syntax=docker/dockerfile:1.4
FROM --platform=$BUILDPLATFORM python:3.10-alpine AS builder

WORKDIR /python_translator

COPY requirements.txt /python_translator
RUN --mount=type=cache,target=/root/.cache/pip \
    pip3 install -r requirements.txt

COPY . /python_translator

#ENTRYPOINT [""]
#CMD ["app.py"]
CMD ["gunicorn", "--reload", "--bind", "0.0.0.0:5000", "wsgi:app"]
EXPOSE 5000

FROM builder as dev-envs

RUN <<EOF
apk update
apk add git
EOF

RUN <<EOF
addgroup -S docker
adduser -S --shell /bin/bash --ingroup docker vscode
EOF
# install Docker tools (cli, buildx, compose)
COPY --from=gloursdocker/docker / /