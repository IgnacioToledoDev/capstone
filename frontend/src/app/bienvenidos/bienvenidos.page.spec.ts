import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BienvenidosPage } from './bienvenidos.page';

describe('BienvenidosPage', () => {
  let component: BienvenidosPage;
  let fixture: ComponentFixture<BienvenidosPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(BienvenidosPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
