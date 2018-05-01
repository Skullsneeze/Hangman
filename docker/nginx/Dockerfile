FROM nginx:latest
ARG user
ENV USER ${user}

RUN echo "Creating user: $USER"

# Create local user inside the container
RUN useradd -ms /bin/bash "$USER"
# Add local user to the web group (eg. www-data)
RUN usermod -a -G www-data "$USER"

ENV TERM xterm-256color