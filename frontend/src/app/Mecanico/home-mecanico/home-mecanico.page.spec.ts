import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HomeMecanicoPage } from './home-mecanico.page';

describe('HomeMecanicoPage', () => {
  let component: HomeMecanicoPage;
  let fixture: ComponentFixture<HomeMecanicoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeMecanicoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
