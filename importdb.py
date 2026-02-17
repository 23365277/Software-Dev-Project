import subprocess

with open("src/database/init.sql", "r", encoding="utf-8") as f:
    subprocess.run(
            [
                "docker",
                "exec",
                "-i",
                "roamance_db",
                "mysql",
                "-u",
                "root",
                "-prootpassword",
                "roamance",
            ],
            stdin=f,
            check=True
    )
