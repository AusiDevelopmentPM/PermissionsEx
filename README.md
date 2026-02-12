# PEX – PocketMine Permission Extension

> Lightweight, fast and developer-friendly permission management system for PocketMine-MP.

PEX is a high-performance permissions plugin for **PocketMine-MP** that manages groups, ranks, prefixes, suffixes, and individual player permissions – with MySQL or YAML storage support.

---

## ✨ Features

- Groups & Ranks
- Prefix & Suffix Support
- MySQL or YAML Storage
- Group Inheritance
- Temporary Ranks
- Player-specific Permissions
- Public API for Developers
- Internal Caching System
- Async MySQL Support
- Optimized for large networks

---

## 📦 Installation

1. Download the `.phar` file.
2. Place it into your `plugins/` directory.
3. Start or restart your server.
4. Configure the `config.yml`.

---

## ⚙️ Configuration

Example `config.yml`:

```yaml
storage:
  type: mysql # mysql or yaml

mysql:
  host: 127.0.0.1
  user: root
  password: password
  database: pex
  port: 3306

default-group: default
```

---

## 🧩 Group Structure

Example `groups.yml`:

```yaml
default:
  prefix: "§7[Player] "
  suffix: ""
  permissions:
    - example.permission
  inheritance: []

admin:
  prefix: "§c[Admin] "
  suffix: ""
  permissions:
    - "*"
  inheritance:
    - default
```

---

## 🛠 Commands

| Command | Description | Permission |
|----------|------------|------------|
| `/pex user <player> add <permission>` | Add a permission to a player | pex.command.user |
| `/pex user <player> remove <permission>` | Remove a permission from a player | pex.command.user |
| `/pex group <group> create` | Create a group | pex.command.group |
| `/pex group <group> delete` | Delete a group | pex.command.group |
| `/pex group <group> addperm <permission>` | Add a permission to a group | pex.command.group |
| `/pex reload` | Reload the plugin | pex.command.reload |

---

## 📚 API Usage

```php
use PEX\API\PermissionAPI;

$api = PermissionAPI::getInstance();

$api->addPermission($player, "example.permission");
$group = $api->getPlayerGroup($player);
```

---

## 🧠 Performance

- Internal permission caching
- Asynchronous database queries
- Minimal server impact
- Designed for high-load environments

---

## 🔐 Permissions

```yaml
pex.command.user
pex.command.group
pex.command.reload
pex.admin
```

---

## 🔄 Compatibility

- PocketMine-MP 5.x
- PHP 8.3+
- Supports CityBuild, Minigames & Network setups

---

## 🗄 Storage Options

| Type | Description |
|------|------------|
| YAML | Recommended for small servers |
| MySQL | Recommended for large networks |

---

## 📜 License

MIT License  
Free to use for private and commercial projects.

---

## 💬 Support

If you encounter issues:
- Create an issue
- Contact the developer

---

## 🚀 Roadmap

- Web Interface
- LuckPerms Import Tool
- Vault-like API Bridge
- GUI Management
- Redis Support
