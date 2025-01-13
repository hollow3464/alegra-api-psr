{
  description = "PHP 8.3 development environment";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs?ref=nixos-24.05";
  };

  outputs = { self, nixpkgs }:
    let
      system = "x86_64-linux";
      pkgs = import nixpkgs {inherit system; };
    in {
      devShells.${system}.default = with pkgs; mkShell {
        buildInputs = [
          php83
          php83Extensions.gd
          php83Extensions.ds
          php83Extensions.pdo
          php83Extensions.dom
          php83Extensions.intl
          php83Extensions.curl
          php83Extensions.posix
          php83Extensions.iconv
          php83Extensions.xdebug
          php83Extensions.mysqli
          php83Extensions.mysqlnd
          php83Extensions.sqlite3
          php83Extensions.pgsql
          php83Extensions.readline
          php83Extensions.posix
        ];
      };
    };
}
