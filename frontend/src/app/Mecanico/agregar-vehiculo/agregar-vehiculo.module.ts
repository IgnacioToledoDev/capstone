import { NgModule } from '@angular/core';
import { CommonModule} from '@angular/common';
import { FormsModule ,ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AgregarVehiculoPageRoutingModule } from './agregar-vehiculo-routing.module';

import { AgregarVehiculoPage } from './agregar-vehiculo.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AgregarVehiculoPageRoutingModule,
    ReactiveFormsModule
  ],
  declarations: [AgregarVehiculoPage]
})
export class AgregarVehiculoPageModule {}
