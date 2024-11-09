import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AgendarPageRoutingModule } from './agendar-routing.module';

import { AgendarPage } from './agendar.page';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        IonicModule,
        AgendarPageRoutingModule,
        ReactiveFormsModule
    ],
  declarations: [AgendarPage]
})
export class AgendarPageModule {}
