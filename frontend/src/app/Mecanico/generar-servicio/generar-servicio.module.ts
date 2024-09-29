import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { GenerarServicioPageRoutingModule } from './generar-servicio-routing.module';

import { GenerarServicioPage } from './generar-servicio.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    GenerarServicioPageRoutingModule
  ],
  declarations: [GenerarServicioPage]
})
export class GenerarServicioPageModule {}
