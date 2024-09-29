import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule,ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RegisterUserPageRoutingModule } from './register-user-routing.module';

import { RegisterUserPage } from './register-user.page';
import { ComponentsModule } from 'src/app/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RegisterUserPageRoutingModule,
    ComponentsModule,
    ReactiveFormsModule
  ],
  declarations: [RegisterUserPage]
})
export class RegisterUserPageModule {}
