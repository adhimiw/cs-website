#!/usr/bin/env python3
"""
Hostinger Paramiko SSH Automation Client for ClimbSphere.
Allows executing remote commands and transfers via Paramiko on Hostinger shared hosting.
"""

import os
import sys
import paramiko

HOST = os.getenv("HSSH_HOST", "145.79.210.59")
PORT = int(os.getenv("HSSH_PORT", "65002"))
USER = os.getenv("HSSH_USER", "u244089748")
PASSWORD = os.getenv("HSSH_PASSWORD", "IdlypoDa@12")
KEY_PATH = os.path.expanduser(os.getenv("HSSH_KEY", "~/.ssh/hostinger_u244089748_ed25519"))

class HostingerClient:
    def __init__(self, host=HOST, port=PORT, username=USER, password=PASSWORD, key_path=KEY_PATH):
        self.host = host
        self.port = port
        self.username = username
        self.password = password
        self.key_path = key_path
        self.client = None

    def connect(self):
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        # Try key auth first if available
        if os.path.exists(self.key_path):
            try:
                ssh.connect(
                    hostname=self.host,
                    port=self.port,
                    username=self.username,
                    key_filename=self.key_path,
                    timeout=15
                )
                self.client = ssh
                return self
            except Exception as e:
                pass
        
        # Fallback to password authentication
        ssh.connect(
            hostname=self.host,
            port=self.port,
            username=self.username,
            password=self.password,
            timeout=15
        )
        self.client = ssh
        return self

    def exec(self, command, timeout=60):
        if not self.client:
            self.connect()
        stdin, stdout, stderr = self.client.exec_command(command, timeout=timeout)
        out = stdout.read().decode()
        err = stderr.read().decode()
        code = stdout.channel.recv_exit_status()
        return code, out, err

    def sftp(self):
        if not self.client:
            self.connect()
        return self.client.open_sftp()

    def upload_file(self, local_path, remote_path):
        sftp = self.sftp()
        # Ensure remote directory exists
        remote_dir = os.path.dirname(remote_path)
        self.exec(f"mkdir -p {remote_dir}")
        sftp.put(local_path, remote_path)
        sftp.close()

    def download_file(self, remote_path, local_path):
        sftp = self.sftp()
        sftp.get(remote_path, local_path)
        sftp.close()

    def close(self):
        if self.client:
            self.client.close()
            self.client = None

    def __enter__(self):
        return self.connect()

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.close()

if __name__ == "__main__":
    cmd = " ".join(sys.argv[1:]) if len(sys.argv) > 1 else "whoami && uname -a"
    with HostingerClient() as client:
        code, out, err = client.exec(cmd)
        if out:
            print(out, end="")
        if err:
            print(err, file=sys.stderr, end="")
        sys.exit(code)
