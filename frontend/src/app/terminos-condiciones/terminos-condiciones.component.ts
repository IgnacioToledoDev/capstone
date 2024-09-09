import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-terminos-condiciones',
  templateUrl: './terminos-condiciones.component.html',
  styleUrls: ['./terminos-condiciones.component.scss'],
})
export class TerminosCondicionesComponent  implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {}
  aceptarTerminos() {
    alert('Has aceptado los términos y condiciones.');
    this.router.navigate(['/bienvenidos']);
  }
}
