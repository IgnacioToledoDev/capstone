import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-recuperar-contrasena',
  templateUrl: './recuperar-contrasena.page.html',
  styleUrls: ['./recuperar-contrasena.page.scss'],
})
export class RecuperarContrasenaPage implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {
  }
  recuperarcontrasena() {
    alert('El mensaje se ha enviado correctamente.');
    this.router.navigate(['/inicio-sesion']);
  }

}
