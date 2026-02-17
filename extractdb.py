import subprocess

with open("src/database/init.sql", "w", encoding="utf-8") as f:
    subprocess.run(
        [
            "docker",
            "exec",
            "roamance_db",
            "mysqldump",
            "-u",
            "root",
            "-prootpassword",
            "roamance",
        ],
        stdout=f,
        check=True,
    )
