import { NgModule } from '@angular/core';
import { CommonModule} from '@angular/common';
import { FormsModule ,ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AgregarVehiculoPageRoutingModule } from './agregar-vehiculo-routing.module';

import { AgregarVehiculoPage } from './agregar-vehiculo.page';
import { ComponentsModule } from 'src/app/components/components.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AgregarVehiculoPageRoutingModule,
    ReactiveFormsModule,
    ComponentsModule,
  ],
  declarations: [AgregarVehiculoPage]
})
export class AgregarVehiculoPageModule {}
