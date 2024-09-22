import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { MenuComponent } from './menu/menu.component';

@NgModule({
  declarations: [MenuComponent], // Declaras el componente
  imports: [
    CommonModule,
    IonicModule
  ],
  exports: [
    MenuComponent, // Exportas el componente
  ],
})
export class ComponentsModule { }
