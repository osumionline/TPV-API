<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class EmpleadoDTO implements ODTO {
  private ?int $id = null;
	private string $nombre = '';
  private bool $has_password = false;
  private ?string $password = null;
  private ?string $confirm_password = null;
  private ?string $color = null;
  private array $roles = [];

  public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
  public function getNombre(): string {
		return $this->nombre;
	}
	private function setNombre(string $nombre): void {
		$this->nombre = $nombre;
	}
  public function getHasPassword(): bool {
		return $this->has_password;
	}
	private function setHasPassword(bool $has_password): void {
		$this->has_password = $has_password;
	}
  public function getPassword(): ?string {
		return $this->password;
	}
	private function setPassword(?string $password): void {
		$this->password = $password;
	}
  public function getConfirmPassword(): ?string {
		return $this->confirm_password;
	}
	private function setConfirmPassword(?string $confirm_password): void {
		$this->confirm_password = $confirm_password;
	}
  public function getColor(): ?string {
		return $this->color;
	}
	private function setColor(?string $color): void {
		$this->color = $color;
	}
  public function getRoles(): array {
		return $this->roles;
	}
	private function setRoles(array $roles): void {
		$this->roles = $roles;
	}

  public function isValid(): bool {
		return (!is_null($this->getNombre()));
	}

  public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
  	$this->setNombre( $req->getParamString('nombre') );
    $this->setHasPassword( $req->getParamBool('hasPassword') );
    $this->setPassword( $req->getParamString('password') );
    $this->setConfirmPassword( $req->getParamString('confirmPassword') );
    $this->setColor( $req->getParamString('color') );
    $this->setRoles( $req->getParam('roles') );
  }
}
