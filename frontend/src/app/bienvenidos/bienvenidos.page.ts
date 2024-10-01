import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { MenuController } from '@ionic/angular';

@Component({
  selector: 'app-bienvenidos',
  templateUrl: './bienvenidos.page.html',
  styleUrls: ['./bienvenidos.page.scss'],
})
export class BienvenidosPage implements OnInit {

  constructor(private menu: MenuController) { }

  ngOnInit() {
  }
  openMenu() {
    this.menu.open();  // Abre el menú manualmente si lo necesitas
  }

}
